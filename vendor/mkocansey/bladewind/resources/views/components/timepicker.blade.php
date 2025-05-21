@props([
    // name of the datepicker. This name is used when posting the form with the datepicker
    'name' => defaultBladewindName(),
    'hourLabel' => config('bladewind.timepicker.hour_label', __("bladewind::bladewind.timepicker_hour_label")),
    'minuteLabel' => config('bladewind.timepicker.minute_label', __("bladewind::bladewind.timepicker_minute_label")),
    'formatLabel' => config('bladewind.timepicker.format_label', __("bladewind::bladewind.timepicker_format_label")),
    'required' => false,
      // what should the time hours be displayed as. Available options are 12, 24
    'format' => config('bladewind.timepicker.format', '12'),
    'selectedValue' => '',
    'style' => config('bladewind.timepicker.style', 'popup'),
    'label' => '',
    'placeholder' => config('bladewind.timepicker.placeholder', __("bladewind::bladewind.timepicker_placeholder")),
])
@php
    $name = parseBladewindName($name);
    if(!empty($selectedValue)) {
        $selected_time_array = explode(':', str_replace(' ', '', $selectedValue));
        $selected_hour = $selected_time_array[0];
        $selected_minute = substr($selected_time_array[1], 0, 2);
        $selected_format = (strlen($selected_time_array[1]) > 2) ? strtoupper(substr($selected_time_array[1], 2, 2)) : '';
    }
@endphp
@if($style == 'popup')
    <div style="width: 125px"
         class="inline-flex items-center align-middle bw-timepicker-{{$name}}">
        <x-bladewind::input
                class="bw-time-{{$name}}"
                :name="$name"
                suffix="clock"
                :required="$required"
                suffix_is_icon="true"
                :add_clearing="false"
                onclick="openTimepicker('{{$name}}')"
                :selected_value="$selectedValue"
                :placeholder="$placeholder"
                :label="$label"/>
        @once
            <div class="hidden clear-time">
                <x-bladewind::icon
                        name="x-circle" type="solid"
                        class="ml-1 opacity-70 hover:opacity-100 cursor-pointer"/>
            </div>
            <script>
                const clockIcon = domEl('.bw-timepicker-{{$name}} .suffix').innerHTML;
                const clearIcon = domEl('.clear-time').innerHTML;
            </script>
        @endonce
    </div>
    <x-bladewind::modal
            title="{{ __('bladewind::bladewind.timepicker_popup_title') }}"
            name="{{$name}}"
            cancel_button_label=""
            ok_button_label="{{ __('bladewind::bladewind.okay') }}"
            ok_button_action="setTime('{{$name}}', '{{$format}}');"
            show_cancel_button="false"
            align_buttons="center">
        <div class="flex justify-center pt-4 pb-3">
            <div>
                <x-bladewind::input
                        numeric="true"
                        tabindex="1"
                        max="{{($format=='12' ? 12 : 23)}}"
                        selected_value="{{$selected_hour??''}}"
                        class="w-[105px] text-center border-2 border-gray-200/70 rounded-md !px-4 !py-5 text-5xl font-semibold opacity-80 bw-{{$name}}_hh"
                        placeholder="{{$hourLabel}}"
                        enforce_limits="true"
                        onpaste="event.preventDefault();"
                        onkeyup="moveToMinutes('{{$name}}');"/>
            </div>
            <div class="px-3 text-center pt-2.5">
                <div class="block size-3 bg-gray-500 my-4 rounded-full"></div>
                <div class="block size-3 bg-gray-500 rounded-full"></div>
            </div>
            <div>
                <x-bladewind::input
                        numeric="true"
                        max="59"
                        tabindex="2"
                        data-time-format="{{$format}}"
                        class="w-[105px] text-center border-2 border-gray-200/70 rounded-md !px-2 !py-5 text-5xl font-semibold opacity-80 bw-{{$name}}_mm"
                        selected_value="{{$selected_minute??''}}"
                        placeholder="{{$minuteLabel}}"
                        onpaste="event.preventDefault();"
                        enforce_limits="true"/>
            </div>
            @if($format  == '12')
                <div class="pl-3 space-y-1">
                    <div tabindex="3"
                         class="rounded-t-lg font-semibold cursor-pointer text-2xl px-4 py-2 {{ (!empty($selected_format) && $selected_format == 'AM') ? 'bg-gray-500 text-white' : 'bg-gray-100 hover:bg-gray-300' }} bw-{{$name}}-time-format-am"
                         onclick="toggleFormat('AM', '{{$name}}');">
                        {{ __('bladewind::bladewind.timepicker_am') }}
                    </div>
                    <div tabindex="4"
                         class="rounded-b-lg font-semibold cursor-pointer text-2xl px-4 py-2 {{ (!empty($selected_format) && $selected_format == 'PM') ? 'bg-gray-500 text-white' : 'bg-gray-100 hover:bg-gray-300' }} bw-{{$name}}-time-format-pm"
                         onclick="toggleFormat('PM', '{{$name}}');">
                        {{ __('bladewind::bladewind.timepicker_pm') }}
                    </div>
                    <input type="hidden" class="bw-{{$name}}_format bg-gray-500"/>
                </div>
            @endif
        </div>
    </x-bladewind::modal>
    @once
        <script>
            const openTimepicker = (name) => {
                showModal(`${name}`);
                window.setTimeout(() => {
                    domEl(`.bw-${name}_hh`).focus();
                    window.clearTimeout();
                }, 300);
            }

            const toggleFormat = (format, name) => {
                let am = domEl(`.bw-${name}-time-format-am`);
                let pm = domEl(`.bw-${name}-time-format-pm`);
                if (format === 'AM') {
                    changeCss(am, 'bg-gray-500,text-white', 'add', true);
                    changeCss(am, 'bg-gray-100, hover:bg-gray-300', 'remove', true);
                    changeCss(pm, 'bg-gray-500,text-white', 'remove', true);
                    changeCss(pm, 'bg-gray-100, hover:bg-gray-300', 'add', true);
                }
                if (format === 'PM') {
                    changeCss(pm, 'bg-gray-500,text-white', 'add', true);
                    changeCss(pm, 'bg-gray-100, hover:bg-gray-300', 'remove', true);
                    changeCss(am, 'bg-gray-500,text-white', 'remove', true);
                    changeCss(am, 'bg-gray-100, hover:bg-gray-300', 'add', true);
                }
                domEl(`.bw-${name}_format`).value = format;
            }
            const moveToMinutes = (name) => {
                if (domEl(`.bw-${name}_hh`).value.length >= 2) {
                    domEl(`.bw-${name}_mm`).focus();
                }
            }

            const setTime = (name, format) => {
                let field = domEl(`.bw-time-${name}`);
                let suffix = domEl(`.bw-timepicker-${name} .suffix`);

                if (field) {
                    let hour = domEl(`.bw-${name}_hh`).value;
                    hour = (format === '24' && hour.length === 1) ? '0' + hour : hour;
                    let minute = domEl(`.bw-${name}_mm`).value;
                    minute = ':' + ((minute.length === 1) ? '0' + minute : minute);
                    let ampm = domEl(`.bw-${name}_format`).value;
                    let time = `${hour}${minute}${ampm ?? ''}`;

                    if (time.length >= 5) {
                        field.value = time;
                        if (suffix) {
                            suffix.innerHTML = clearIcon.replace('<svg', `<svg onclick="clearTime('${name}')"`);
                        }
                    }
                }
            }

            const clearTime = (name) => {
                let field = domEl(`.bw-time-${name}`);
                let suffix = domEl(`.bw-timepicker-${name} .suffix`);
                field.value = '';
                suffix.innerHTML = clockIcon;
            }
        </script>
    @endonce
@else
    <div class="flex">
        <div>
            <x-bladewind::select
                    data="manual"
                    onselect="setTime_{{$name}}"
                    :placeholder="$hourLabel"
                    name="{{$name}}_hh"
                    :required="$required"
                    selected_value="{{$selected_hour??''}}">
                @for($hours=1; $hours < (($format=='12') ? 13:24); $hours++)
                    @php $hours = (($format=='12') ? $hours : str_pad($hours, 2, '0', STR_PAD_LEFT))  @endphp
                    <x-bladewind::select.item label="{{$hours}}" value="{{$hours}}"/>
                @endfor
                @if($format !== '12')
                    <x-bladewind::select.item label="00" value="00"/>
                @endif
            </x-bladewind::select>
        </div>
        <div class="!-ml-2">
            <x-bladewind::select
                    data="manual"
                    onselect="setTime_{{$name}}"
                    :placeholder="$minuteLabel"
                    name="{{$name}}_mm"
                    :required="$required"
                    selected_value="{{$selected_minute??''}}">
                <x-bladewind::select.item label="00" value="00"/>
                @for($minutes=1; $minutes < 60; $minutes++)
                    @php $minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT) @endphp
                    <x-bladewind::select.item label="{{$minutes}}" value="{{$minutes}}"/>
                @endfor
            </x-bladewind::select>
        </div>
        @if($format == '12')
            <div class="!-ml-2">
                <x-bladewind::select
                        data="manual"
                        onselect="setTime_{{$name}}"
                        :placeholder="$formatLabel"
                        name="{{$name}}_format"
                        :required="$required"
                        selected_value="{{$selected_format??''}}">
                    <x-bladewind::select.item label="{{ __('bladewind::bladewind.timepicker_am') }}" value="AM"/>
                    <x-bladewind::select.item label="{{ __('bladewind::bladewind.timepicker_pm') }}" value="PM"/>
                </x-bladewind::select>
            </div>
        @endif
    </div>
    <input type="hidden" class="bw-time-{{$name}}" name="{{$name}}" value="{{str_replace(' ', '', $selectedValue)}}"/>
    <script>
        const setTime_{{$name}} = () => {
            let field = domEl(`.bw-time-{{$name}}`);
            if (field) {
                let hour = domEl('.bw-{{$name}}_hh').value;
                let minute = ':' + domEl('.bw-{{$name}}_mm').value;
                let format = domEl('.bw-{{$name}}_format').value;
                let time = `${hour}${minute}${format ?? ''}`;
                if (time.length >= 4) {
                    field.value = time;
                }
            }
        }
    </script>
@endif