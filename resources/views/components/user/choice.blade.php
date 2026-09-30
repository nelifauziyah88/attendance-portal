@props(['name', 'value', 'label', 'checked' => false, 'disabled' => false])

<label class="group relative block cursor-pointer">
    <input type="radio" name="{{ $name }}" value="{{ $value }}" @checked($checked) @disabled($disabled) class="sr-only">

    <div class="flex min-h-[52px] items-center gap-3 rounded-xl border-2 border-slate-200 bg-white px-4 py-2.5 text-sm font-medium transition duration-300 hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-lg hover:shadow-blue-100 group-has-[:checked]:border-[#3563ff] group-has-[:checked]:bg-blue-50 group-has-[:focus-visible]:ring-4 group-has-[:focus-visible]:ring-blue-100">
        <span class="grid size-5 shrink-0 place-items-center rounded-full border-2 border-slate-300 transition duration-300 group-has-[:checked]:border-[#3563ff]">
            <span class="size-2.5 scale-0 rounded-full bg-[#3563ff] transition duration-300 group-has-[:checked]:scale-100"></span>
        </span>
        <span class="min-w-0">{{ $label }}</span>
    </div>
</label>