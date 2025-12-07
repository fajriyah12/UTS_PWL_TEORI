<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Event;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Event $event)
    {
        return $user->isOrganizer() && $event->organizer_id === $user->organizer->id;
    }

    public function update(User $user, Event $event)
    {
        return $user->isOrganizer() && $event->organizer_id === $user->organizer->id;
    }

    public function delete(User $user, Event $event)
    {
        return $user->isOrganizer() && $event->organizer_id === $user->organizer->id;
    }
}