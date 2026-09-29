<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('bookings:cancel-expired')->everyMinute();

Schedule::command('queue:work --stop-when-empty')->everyMinute();