<?php

class FondOf_Contentful_Model_Client_Logger implements \Contentful\Log\LoggerInterface
{

    /**
     * Returns a timer to be used to gather timing information for the next request.
     *
     * @return \Contentful\Log\TimerInterface
     */
    public function getTimer()
    {
        return new \Contentful\Log\StandardTimer();
    }

    /**
     * Log information about a request.
     *
     * @param float $api
     * @param \Psr\Http\Message\RequestInterface $request
     * @param \Contentful\Log\TimerInterface $timer
     * @param \Psr\Http\Message\ResponseInterface|null $response
     * @param \Exception|null $exception
     *
     * @return void
     */
    public function log($api, \Psr\Http\Message\RequestInterface $request, \Contentful\Log\TimerInterface $timer, \Psr\Http\Message\ResponseInterface $response = null, \Exception $exception = null)
    {
        $logEntry = array(
            'API: ' . $api,
            'Duration: ' . $timer->getDuration(),
            'Request: ' . \GuzzleHttp\Psr7\str($request)
        );

        if ($response != null) {
            $logEntry[] = 'Response: ' . \GuzzleHttp\Psr7\str($response);
        }

        if ($exception != null) {
            $logEntry[] = 'Exception: ' . $exception->getMessage();
        }

        Mage::log($logEntry, Zend_Log::DEBUG, 'fondof_contentful.log');
    }
}
