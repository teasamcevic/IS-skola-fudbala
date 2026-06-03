<?php

namespace Tests\Feature;

use Tests\TestCase;

class TrustedProxyAssetTest extends TestCase
{
    public function test_assets_use_forwarded_https_host_behind_proxy(): void
    {
        $this->withServerVariables(['REMOTE_ADDR' => '10.0.0.1'])
            ->withHeaders([
                'X-Forwarded-Host' => 'example.up.railway.app',
                'X-Forwarded-Proto' => 'https',
            ])
            ->get('/')
            ->assertOk()
            ->assertSee('href="https://example.up.railway.app/css/style.css"', false);
    }
}
