<div class="layout-wrapper layout-navbar-fixed">
    <div class="w-full" style="padding-top:64px">
        <nav class="layout-navbar navbar navbar-expand-lg bg-gradient-primary">
            <div class="container">
                <div class="d-flex align-items-center">
                    <a href="/" class="text-white mt-1" wire:navigate>
                        <span class="solar--arrow-left-line-duotone me-4"></span>
                    </a>
                    <span class="text-lg text-white fw-bold">Checkout</span>
                </div>
            </div>
        </nav>

        <div class="bg-gradient-primary pb-12">
            <div class="container container-p-y">
                <h5 class="text-white">Lakukan Pembayaran</h5>
                <p class="text-white">Kamu akan menerima kode voucher setelah pembayaran berhasil.</p>
            </div>
        </div>

        <div class="container mt-n12">

            <div class="row">
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Detail Pesanan</h5>
                        </div>
                        <table class="table">
                            <tr>
                                <td>Paket</td>
                                <td class="text-end text-black fw-bold">{{ $voucher->name }}</td>
                            </tr>
                            <tr>
                                <td>Kuota</td>
                                <td class="text-end text-black fw-bold">Unlimited</td>
                            </tr>
                            <tr>
                                <td>Kecepatan</td>
                                <td class="text-end text-black fw-bold">Upto {{ $voucher->speed }}</td>
                            </tr>
                            <tr>
                                <td>Batas sambungan</td>
                                <td class="text-end text-black fw-bold">{{ $voucher->connections }} Perangkat</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-lg-4 pb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">Pembayaran</h5>
                        </div>
                        <table class="table">
                            <tr>
                                <td>Harga</td>
                                <td class="text-end text-black fw-bold">Rp {{ number_format($voucher->price, 0) }}</td>
                            </tr>
                            <tr>
                                <td>Metode pembayaran</td>
                                <td class="text-end text-black fw-bold">QRIS</td>
                            </tr>
                        </table>
                        <div class="card-footer">
                            <a href="/payout" class="btn btn-primary w-100">Bayar</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
