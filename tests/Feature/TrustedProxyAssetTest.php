<?php

namespace Tests\Feature;

use Tests\TestCase;

class TrustedProxyAssetTest extends TestCase
{
    public function test_public_urls_use_forwarded_https_host_behind_proxy(): void
    {
        $this->withServerVariables(['REMOTE_ADDR' => '10.0.0.1'])
            ->withHeaders([
                'X-Forwarded-Host' => 'example.up.railway.app',
                'X-Forwarded-Proto' => 'https',
            ])
            ->get('/login')
            ->assertRedirect('http://127.0.0.1:4200/login');
    }
}
