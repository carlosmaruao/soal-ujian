<div class="container box-container">
    @if (session()->has('success'))
    <div class="box-alert" style="margin-bottom:0; width:100%; text-align:center">
        <div class="alert alert-success" style="width: 100%">
            {{ session()->get('success') }}
        </div>
    </div>
    @else
    <div class="box-no-alert"></div>
    @endif

    @if (session()->has('error'))
    <div class="box-alert" style="margin-bottom:0; width:100%; text-align:center">
        <div class="alert alert-danger" style="width: 100%">
            {{ session()->get('error') }}
        </div>
    </div>
    @else
    <div class="box-no-alert"></div>
    @endif

    @if (Auth::check() && Auth::user()->status == 0 && Auth::user()->kelengkapan == 2)
    <div class="box-alert" style="margin-bottom:0; width:100%; text-align:center">
        <div class="alert alert-info" style="width: 100%">
            <strong>Bukti Pembayaran Anda sedang Kami Proses .. Terima Kasih</strong>
        </div>
    </div>
    @else
    <div class="box-no-alert"></div>
    @endif
</div>