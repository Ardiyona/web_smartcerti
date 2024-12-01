<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifikasiPesertaSertifikasi extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private $sertifikasi;
    public function __construct($sertifikasi)
    {
        $this->sertifikasi = $sertifikasi;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'id_sertifikasi' => $this->sertifikasi->id_sertifikasi,
            'user_id' => $this->sertifikasi->detail_peserta_sertifikasi->pluck('user_id')->implode(', '),
            'id_vendor' => $this->sertifikasi->vendor_sertifikasi->nama,
            'id_jenis_sertifikasi' => $this->sertifikasi->jenis_sertifikasi->nama_jenis_sertifikasi,
            'biaya' => $this->sertifikasi->biaya,
            'tanggal' => $this->sertifikasi->tanggal,
            'jenis' => $this->sertifikasi->jenis,
            'title' => 'Sertifikasi',
            'massages' => 'Terdapat kegiatan ' . $this->sertifikasi->nama_sertifikasi,
        ];
    }
}
