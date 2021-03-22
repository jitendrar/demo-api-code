<?php

namespace App\Http\Middleware;


use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class LogRequest
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];

    public function handle($request, \Closure $next)
    {
        
        $output = "[%datetime%] : %message% \n";
        $formatter = new LineFormatter($output);
        $streamHandler = new StreamHandler(storage_path('logs/RQ_LOG-'.date('Y-m-d').'.log'));
        $streamHandler->setFormatter($formatter);
        $this->Log = new Logger('RQ_LOG');
        $this->Log->pushHandler($streamHandler);
        // $this->Log = new Logger('RQ_LOG');
        // $this->Log->pushHandler(new StreamHandler(storage_path('logs/RQ_LOG-'.date('Y-m-d').'.log')));
        $ip=$request->ip();
        $url=$request->fullUrl();
        $method = $request->method();
        $Rdata = json_encode($request->all());
        $log = $ip. " - ".$method . " - " . $url . " ==> " .$Rdata;
		$this->Log->info($log);
        return $next($request);
    }

    public function terminate($request, $response)
    {
        $output = "[%datetime%] : %message% \n";
        $formatter = new LineFormatter($output);
        $streamHandler = new StreamHandler(storage_path('logs/RQ_LOG-'.date('Y-m-d').'.log'));
        $streamHandler->setFormatter($formatter);
        $this->Log = new Logger('RQ_LOG');
        $this->Log->pushHandler($streamHandler);
        
    	// $this->Log = new Logger('RQ_LOG');
     //    $this->Log->pushHandler(new StreamHandler(storage_path('logs/RQ_LOG-'.date('Y-m-d').'.log')));

        $ip=$request->ip();
        $url=$request->fullUrl();
        $Rdata = json_encode($request->all());
        $log = "IP : ". $ip . " URL : " . $url . " Data ==> " .$Rdata . " Response : ".$response;
		$this->Log->info($log);
    }
}
