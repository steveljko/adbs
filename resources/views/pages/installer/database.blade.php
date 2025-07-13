<div id="content">
    <h1 class="text-2xl text-center">Database</h1>
    <form hx-post="{{ route('installer.database.setup') }}" hx-target="#content" hx-swap="outerHTML"
        hx-include="#db_driver">
        <x-form.select id="db_driver" name="db_driver" label="Database Driver" placeholder="Database Driver"
            :options="$options" :value="$selectedDriver ?? ''" hx-get="{{ route('installer.database.select') }}"
            hx-include="#db_driver" hx-target="#fcontent" hx-swap="innerHTML" />
        <div id="fcontent"></div>
        <div class="flex justify-between">
            <x-button type="submit">Test & Save Configuration</x-button>
        </div>
    </form>
</div>
