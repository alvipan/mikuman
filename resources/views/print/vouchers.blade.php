@php
    $colors = [
        'blue' => [
            'light' => '#e0e7ff',
            'main' => '#4f6df5',
        ],
        'green' => [
            'light' => '#d1fae5',
            'main' => '#10b981',
        ],
        'red' => [
            'light' => '#ffe4e6',
            'main' => '#f43f5e',
        ],
        'yellow' => [
            'light' => '#fef3c7',
            'main' => '#f59e0b',
        ],
    ];

    $c = $colors[$color] ?? $colors['blue'];
@endphp

<!DOCTYPE html>
<html>

    <head>
        <title>Print Voucher</title>

        <style>
            @page {
                size: A4;
                margin: 5mm;
            }

            body {
                margin: 0;
                font-family: Arial, sans-serif;
            }

            .grid {
                display: grid;
                grid-template-columns: repeat(5, 1fr);
            }

            .voucher {
                background: linear-gradient(135deg, #f8fafc 50%, {{ $c['light'] }} 50%);
                height: 24.75mm;
                border: 0.5px dashed #ddd;
                box-sizing: border-box;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                overflow: hidden;
            }

            /* HEADER */
            .header {
                display: flex;
                justify-content: space-between;
                padding: 4px;
                font-size: 10px;
                font-weight: bold;
            }

            .price {
                color: {{ $c['main'] }};
                font-size: 16px;
                font-weight: bold;
            }

            /* BODY */
            .body {
                display: flex;
                column-gap: 4px;
                flex: 1;
            }

            .left {
                flex: 1;
                padding: 4px;
            }

            .label {
                font-size: 8px;
                color: #666;
            }

            .code-box {
                border: 1px dashed #333;
                border-radius: 4px;
                text-align: center;
                padding: 4px;
                margin-top: 4px;
            }

            .code {
                font-size: 12px;
                font-weight: bold;
                letter-spacing: 1px;
            }

            /* QR */
            .qr {
                width: 56px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: -22px;
                padding-right: 4px;
                z-index: 1;
            }

            .qr img {
                width: 56px;
                height: 56px;
            }

            /* FOOTER */
            .footer {
                background: {{ $c['main'] }};
                color: white;
                font-size: 10px;
                text-align: left;
                padding: 4px;
            }
        </style>

        <script>
            window.onload = () => window.print();
        </script>
    </head>

    <body>

        <div class="grid">

            @foreach ($vouchers as $v)
                <div class="voucher">

                    {{-- HEADER --}}
                    <div class="header">
                        <div>{{ $v->router->name ?? 'WiFi' }}</div>
                        <div class="price">Rp {{ number_format($v->sale_price ?? 0) }}</div>
                    </div>

                    {{-- BODY --}}
                    <div class="body">

                        <div class="left">
                            <div class="label">KODE VOUCHER</div>

                            <div class="code-box">
                                <div class="code">{{ $v->username }}</div>
                            </div>
                        </div>

                        @if ($showQr)
                            <div class="qr">
                                <img
                                    src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data={{ $v->username }}">
                            </div>
                        @endif

                    </div>

                    {{-- FOOTER --}}
                    <div class="footer">
                        {{ $v->profile->name ?? '1 Hari' }}
                    </div>

                </div>
            @endforeach

            {{-- FILL SLOT --}}
            @for ($i = $vouchers->count(); $i < 60; $i++)
                <div class="voucher"></div>
            @endfor

        </div>

    </body>

</html>
