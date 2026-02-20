<?php

use OCA\Talk\Events\BeforeCallStartedEvent;
use OCA\Talk\Events\BeforeCallEndedEvent;
use OCA\Talk\Events\BeforeUserJoinedRoomEvent;
use OCA\Talk\Events\BeforeAttendeesAddedEvent;
use OCA\Talk\Events\LobbyModifiedEvent;
use OCA\Talk\Events\SessionLeftRoomEvent;
use OCA\Talk\Events\RoomModifiedEvent;


enum TalkEvents: string
{
    case BEFORE_CALL_STARTED = BeforeCallStartedEvent::class;
    case BEFORE_CALL_ENDED = BeforeCallEndedEvent::class;
    case BEFORE_USER_JOINED_ROOM = BeforeUserJoinedRoomEvent::class;
    case BEFORE_ATTENDEES_ADDED = BeforeAttendeesAddedEvent::class;
    case LOBBY_MODIFIED = LobbyModifiedEvent::class;
    case SESSION_LEFT_ROOM = SessionLeftRoomEvent::class;
    case ROOM_MODIFIED = RoomModifiedEvent::class;

    public function getEventClass(): string
    {
        return $this->value;
    }

    public function eventName(): string
    {
        return match ($this) {
            self::BEFORE_CALL_STARTED => 'Before Call Started',
            self::BEFORE_CALL_ENDED => 'Before Call Ended',
            self::BEFORE_USER_JOINED_ROOM => 'Before User Joined Room',
            self::BEFORE_ATTENDEES_ADDED => 'Before Attendees Added',
            self::LOBBY_MODIFIED => 'Lobby Modified',
            self::SESSION_LEFT_ROOM => 'Session Left Room',
            self::ROOM_MODIFIED => 'Room Modified',
        };
    }
}
