<div class="bg-white rounded-2xl p-6 border border-slate-200/60 animate-pulse">
    <div class="flex items-center justify-between mb-4">
        <div class="h-4 w-40 bg-slate-200 rounded"></div>
        <div class="h-3 w-20 bg-slate-200 rounded"></div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200/60">
                    <th class="pb-2">
                        <div class="h-3 w-6 bg-slate-200 rounded"></div>
                    </th>
                    <th class="pb-2">
                        <div class="h-3 w-16 bg-slate-200 rounded"></div>
                    </th>
                    <th class="pb-2">
                        <div class="h-3 w-16 bg-slate-200 rounded"></div>
                    </th>
                    <th class="pb-2 text-right">
                        <div class="h-3 w-12 bg-slate-200 rounded ml-auto"></div>
                    </th>
                    <th class="pb-2 text-right">
                        <div class="h-3 w-16 bg-slate-200 rounded ml-auto"></div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < 5; $i++)
                    <tr class="border-b border-slate-100">
                        <td class="py-2">
                            <div class="h-3 w-4 bg-slate-200 rounded"></div>
                        </td>
                        <td class="py-2">
                            <div class="h-4 w-32 bg-slate-200 rounded"></div>
                        </td>
                        <td class="py-2">
                            <div class="h-5 w-16 bg-slate-200 rounded"></div>
                        </td>
                        <td class="py-2 text-right">
                            <div class="h-3 w-12 bg-slate-200 rounded ml-auto"></div>
                        </td>
                        <td class="py-2 text-right">
                            <div class="h-3 w-16 bg-slate-200 rounded ml-auto"></div>
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
