<x-layouts.registrar title="Registrar Dashboard">
    @livewire('registrar.registrar-dashboard-manager', [
        'stats' => $stats,
        'notifications' => $notifications,
        'newEnrolleesCount' => $newEnrolleesCount,
        'weekDates' => $weekDates,
        'weekRange' => $weekRange,
        'appsByDate' => $appsByDate
    ])
</x-layouts.registrar>