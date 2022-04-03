<?php

namespace App\Providers;

use App\Models\Attendance;
use App\Models\ClassDetail;
use App\Models\ClassDetailStudent;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Observers\AttendanceObserver;
use App\Observers\ClassDetailObserver;
use App\Observers\ClassDetailStudentObserver;
use App\Observers\RoomObserver;
use App\Observers\ScheduleObserver;
use App\Observers\SectionObserver;
use App\Observers\StudentObserver;
use App\Observers\SubjectObserver;
use App\Observers\TeacherObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        // Product::observe(ProductObserver::class);
        Attendance::observe(AttendanceObserver::class);
        ClassDetail::observe(ClassDetailObserver::class);
        ClassDetailStudent::observe(ClassDetailStudentObserver::class);
        Room::observe(RoomObserver::class);
        Schedule::observe(ScheduleObserver::class);
        Section::observe(SectionObserver::class);
        Student::observe(StudentObserver::class);
        Subject::observe(SubjectObserver::class);
        Teacher::observe(TeacherObserver::class);
    }
}
