<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Event;
use App\Notifications\MyNotifyEvent;

class EventTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:event_time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user=Auth::User();
        $dateCurrent=Carbon::now();
        $events=Event::where(['month'=> $dateCurrent->month,'day'=>$dateCurrent->day]);
        if ($events>0)
        foreach($events as $event)
        {
            
            $from = $event->year;
            $to=$dateCurrent->year; 
            $dif=abs($from-$to);
            if($dif==1){ $count="year";}
            else if ($dif==2) {$count="two years";}
            else if ($dif=3){$count=$dif."years";}  //3-10
            else
            {$count=$dif."year";}
            $event->update(['status'=>1,'time'=>$count]);
            $user->notify(new MyNotifyEvent($event));
        }
    }
}
