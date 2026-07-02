<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DapodikService
{
    protected $url;
    protected $token;
    protected $npsn;

    public function __construct()
    {
        $this->url = config('dapodik.url');
        $this->token = config('dapodik.token');
        $this->npsn = config('dapodik.npsn');
    }

    private function getClient()
    {
        return Http::withToken($this->token)->baseUrl($this->url);
    }

    public function testConnection()
    {
        try {
            $response = $this->getClient()->get('/WebService/getPengguna', [
                'npsn' => $this->npsn
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Dapodik Connection Error: ' . $e->getMessage());
            return false;
        }
    }

    public function fetchSemester()
    {
        // Example implementation for fetching semester data
        try {
            $response = $this->getClient()->get('/WebService/getSemester');
            if ($response->successful()) {
                return $response->json('rows');
            }
            return null;
        } catch (\Exception $e) {
            Log::error('Dapodik fetchSemester Error: ' . $e->getMessage());
            return null;
        }
    }

    public function fetchAllData()
    {
        // Fetch sequence: Sekolah -> PTK -> Siswa -> Rombel -> Pembelajaran
        $status = [];
        
        $status['sekolah'] = $this->fetchData('/WebService/getSekolah', 'Sekolah');
        $status['ptk'] = $this->fetchData('/WebService/getPtk', 'PTK');
        $status['peserta_didik'] = $this->fetchData('/WebService/getPesertaDidik', 'Siswa');
        $status['rombongan_belajar'] = $this->fetchData('/WebService/getRombonganBelajar', 'Rombel');
        $status['pembelajaran'] = $this->fetchData('/WebService/getPembelajaran', 'Pembelajaran');

        return $status;
    }

    private function fetchData($endpoint, $entityName)
    {
        try {
            $response = $this->getClient()->get($endpoint, [
                'npsn' => $this->npsn
            ]);

            if ($response->successful()) {
                $rows = $response->json('rows');
                // Synchronization logic will be implemented here
                // e.g., mapping to local database tables
                
                return [
                    'status' => 'success',
                    'count' => count($rows ?? [])
                ];
            }
            
            return [
                'status' => 'error',
                'message' => 'Failed to fetch'
            ];
        } catch (\Exception $e) {
            Log::error("Dapodik fetch {$entityName} Error: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}
