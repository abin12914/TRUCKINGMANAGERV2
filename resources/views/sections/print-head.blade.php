@if(config('settings.print_head_flag'))
    <div class="box-header visible-print-block text-center">
        <h4>{{ $loggedUser->company->company_name }}</h4>
        <h5>{{ $loggedUser->company->address }}</h5>
        <h6><u>{{ $loggedUser->company->phone }}</u></h6>
    </div>
@endif
<div class="visible-print-block">
    {{-- <h4><u>@yield('title')</u></h4> --}}
</div>
