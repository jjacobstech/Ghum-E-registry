@props([
    'onclick' => '',
    'title' => '',
    'href' => '',
])
@aware([
    'type' => 'primary',
    'color' => '',
    'size' => config('bladewind.button.circle.size', 'regular'),
    'name' => null,
    'canSubmit' => false,
    'disabled' => false,
    'icon' => '',
    'buttonTextCss' => null,
    'showFocusRing' => true,
    'outline' => false,
])

@php if($color == 'secondary') $type = 'secondary'; @endphp

<x-bladewind::button
        :color="$color"
        :size="$size"
        :name="$name"
        :can_submit="$canSubmit"
        :disabled="$disabled"
        :show_focus_ring="$showFocusRing"
        :outline="$outline"
        tag="button"
        circular="true"
        radius="full"
        :icon="$icon"
        :button_text_css="$buttonTextCss"
        :title="$title"
        :type="$type"
        :onclick="$onclick"
        :href="$href"/>