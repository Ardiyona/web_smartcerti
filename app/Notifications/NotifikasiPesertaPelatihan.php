<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifikasiPesertaPelatihan extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private $pelatihan;
    public function __construct($pelatihan)
    {
        $this->pelatihan = $pelatihan;
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
            'id_pelatihan' => $this->pelatihan->id_pelatihan,
            'user_id' => $this->pelatihan->detail_peserta_pelatihan->pluck('user_id')->implode(', '),
            'id_vendor' => $this->pelatihan->vendor_pelatihan->nama,
            'id_jenis_pelatihan' => $this->pelatihan->jenis_pelatihan->nama_jenis_pelatihan,
            'biaya' => $this->pelatihan->biaya,
            'tanggal' => $this->pelatihan->tanggal,
            'level' => $this->pelatihan->level,
            'alamat' => $this->pelatihan->alamat,
            'title' => 'Pelatihan',
            'massages' => 'Terdapat kegiatan ' . $this->pelatihan->nama_pelatihan,
            'url' => url('notifikasi_pelatihan/' . $this->pelatihan->id_pelatihan),
        ];
    }
}
