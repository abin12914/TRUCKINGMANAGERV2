<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" style="width: 100%" tabindex="{{ $tabindex }}">
    <option value="">Select site</option>
    @if(!empty($sitesCombo) && (count($sitesCombo) > 0))
        @foreach($sitesCombo as $site)
            <option value="{{ $site->id }}" {{ (old($selectName) == $site->id || $selectedSiteId == $site->id) ? 'selected' : '' }}>{{ $site->name. ", ". $site->place }}</option>
        @endforeach
    @endif
</select>