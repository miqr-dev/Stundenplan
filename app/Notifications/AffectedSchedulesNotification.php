<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AffectedSchedulesNotification extends Notification
{
  use Queueable;

  private $affectedSchedule;


  public function __construct($affectedSchedulesWithRelations)
  {
    $this->affectedSchedule = $affectedSchedulesWithRelations;
  }

  public function via($notifiable)
  {
    return ['database'];
  }
  // public function toMail($notifiable)
  // {
  //   $message = (new MailMessage)
  //     ->line('Your schedule has been changed for the following:');

  //   foreach ($this->affectedSchedules as $schedule) {
  //     $message->line("Day: {$schedule->day}, Date: {$schedule->date}, Start Time: {$schedule->start_time}, End Time: {$schedule->end_time}, Course: {$schedule->course}");
  //   }

  //   return $message->action('View Schedule', url('/schedule'))
  //     ->line('Thank you for using our application!');
  // }
  public function toArray($notifiable)
  {
    $formattedStartTime = $this->formatTime($this->affectedSchedule->start_time);
    $formattedEndTime = $this->formatTime($this->affectedSchedule->end_time);

    $teacherName = $this->formatTeacherName($this->affectedSchedule->teacher->surname, $this->affectedSchedule->teacher->name);

    $message = sprintf(
      "Urlaub am: %s<br>"
        . "Lehrer: %s<br>"
        . "Kurs: %s<br>"
        . "Fach: %s<br>"
        . "Zeit: %s-%s",
      $this->styleText($this->affectedSchedule->schedualMaster->date, 'orange', 'bold'),
      $this->styleText($teacherName, 'blue', 'bold'),
      optional($this->affectedSchedule->schedualMaster->course)->name,
      optional($this->affectedSchedule->subject)->name,
      $formattedStartTime,
      $formattedEndTime
    );

    return [
      'message' => $message,
      'Day' => $this->affectedSchedule->schedualMaster->date,
      'Start Time' => $formattedStartTime,
      'End Time' => $formattedEndTime,
      'Course' => optional($this->affectedSchedule->schedualMaster->course)->name,
      'Subject' => optional($this->affectedSchedule->subject)->name,
      'Teacher' => $teacherName,
      'Room' => optional($this->affectedSchedule->room)->name,
    ];
  }

  private function formatTime($time)
  {
    return date('H:i', strtotime($time));
  }

  private function formatTeacherName($surname, $name)
  {
    return "$surname, $name";
  }

  private function styleText($text, $color, $fontWeight)
  {
    return "<span style='color: $color; font-weight: $fontWeight;'>$text</span>";
  }
}
