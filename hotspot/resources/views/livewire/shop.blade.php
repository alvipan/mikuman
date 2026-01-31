<div class="layout-wrapper layout-navbar-fixed">
    <div class="w-full" style="padding-top:64px">
        <nav class="layout-navbar navbar navbar-expand-lg bg-primary">
            <div class="container">
                <div class="d-flex align-items-center">
                    <a href="http://10.10.0.1/login.html" class="text-white mt-1">
                        <span class="solar--arrow-left-line-duotone me-4"></span>
                    </a>
                    <span class="text-lg text-white fw-bold">Beli Voucher</span>
                </div>
            </div>
        </nav>

        <div class="bg-primary py-8"></div>

        <div class="container mt-n12">
            <div class="row">
                
                @foreach($vouchers as $voucher)
                <div class="col col-lg-4 col-md-6">
                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex align-items-center mb-4">
                            <div class="avatar p-3 me-4" style="width:45px;height:45px">
                                <span class="avatar-initial bg-label-primary rounded">
                                    <i class="solar--ticket-bold-duotone text-primary" aria-hidden="true"></i>
                                </span>
                            </div>
                            <div class="me-auto">
                                <h6 class="card-title mb-0 fw-bold">{{ $voucher->name }}</h6>
                                <span>Rp {{ number_format($voucher->price, 0) }}</span>
                            </div>
                            <a href="/checkout/{{ $voucher->id }}" class="btn btn-primary mb-0" wire:navigate>Beli</a>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-secondary mb-0">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="solar--global-line-duotone me-3"></span>
                                    <span>Kuota Unlimited</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="solar--spedometer-max-line-duotone me-3"></span>
                                    <span>Kecepatan hingga {{ $voucher->speed }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                
            </div>
        </div>
    </div>
</div>
