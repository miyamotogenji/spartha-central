<?php

namespace App\Console\Commands;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\WhatsappNumber;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ImportWhatsAppHistory extends Command
{
    protected $signature = 'whatsapp:import-history
                            {file? : Ruta al archivo exportado (JSON o TXT de WhatsApp)}
                            {--number= : ID del whatsapp_numbers a asociar}
                            {--phone= : Número de teléfono del contacto (sin +)}';

    protected $description = 'Importa historial de chats exportados manualmente desde WhatsApp Business';

    public function handle(): int
    {
        $file = $this->argument('file');

        if (! $file) {
            $this->info('Uso: php artisan whatsapp:import-history <archivo> --number=<id> [--phone=<tel>]');
            $this->line('');
            $this->line('Exporte el chat desde WhatsApp Business (Sin archivos multimedia) y conviértalo a JSON:');
            $this->line('  [{"from":"59399...","body":"Hola","timestamp":"2026-01-15 10:30:00","direction":"inbound"}]');
            return self::SUCCESS;
        }

        if (! File::exists($file)) {
            $this->error("Archivo no encontrado: {$file}");
            return self::FAILURE;
        }

        $numberId = $this->option('number');
        $number = $numberId
            ? WhatsappNumber::find($numberId)
            : WhatsappNumber::where('connection_status', 'connected')->first();

        if (! $number) {
            $this->error('No hay número WhatsApp conectado. Use --number=<id>.');
            return self::FAILURE;
        }

        $content = File::get($file);
        $messages = json_decode($content, true);

        if (! is_array($messages)) {
            $messages = $this->parseWhatsAppTxt($content);
        }

        if (empty($messages)) {
            $this->error('No se encontraron mensajes en el archivo.');
            return self::FAILURE;
        }

        $phone = preg_replace('/\D+/', '', $this->option('phone') ?? 'imported');
        $conversation = Conversation::firstOrCreate(
            ['whatsapp_number_id' => $number->id, 'contact_phone' => $phone],
            ['contact_name' => $phone, 'queue' => 'closed']
        );

        $imported = 0;
        foreach ($messages as $msg) {
            $body = $msg['body'] ?? $msg['text'] ?? '';
            if ($body === '') {
                continue;
            }

            $direction = ($msg['direction'] ?? 'inbound') === 'outbound' ? 'outbound' : 'inbound';

            Message::firstOrCreate(
                [
                    'conversation_id' => $conversation->id,
                    'body'            => $body,
                    'direction'       => $direction,
                    'type'            => 'text',
                ],
                [
                    'delivered_at' => isset($msg['timestamp']) ? \Carbon\Carbon::parse($msg['timestamp']) : now(),
                ]
            );
            $imported++;
        }

        $this->info("Importados {$imported} mensajes para el contacto {$phone} (número #{$number->id}).");

        return self::SUCCESS;
    }

    private function parseWhatsAppTxt(string $content): array
    {
        $messages = [];
        $lines = preg_split('/\r\n|\r|\n/', $content);

        foreach ($lines as $line) {
            if (preg_match('/^\[(\d{1,2}\/\d{1,2}\/\d{2,4},?\s+\d{1,2}:\d{2}(?::\d{2})?(?:\s*[ap]\.?m\.?)?)\]\s*(.+?):\s*(.+)$/iu', $line, $m)) {
                $messages[] = [
                    'timestamp' => $m[1],
                    'from'      => trim($m[2]),
                    'body'      => trim($m[3]),
                    'direction' => 'inbound',
                ];
            }
        }

        return $messages;
    }
}
