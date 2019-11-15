<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" style="width: 100%" tabindex="{{ $tabindex }}">
    <option value="" {{ empty($selectedServiceId) ? 'selected' : '' }}>Select service</option>
    @if(!empty($servicesCombo) && (count($servicesCombo) > 0))
        @foreach($servicesCombo as $service)
            @if($disableSpecialServices == true && $service->is_special == 1 && $selectedServiceId != $service->id)
                @continue
            @endif
            <option value="{{ $service->id }}" {{ $selectedServiceId == $service->id ? 'selected' : '' }}>
                {{ $service->name }}
            </option>
        @endforeach
    @endif
</select>
