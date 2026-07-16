from __future__ import annotations

from uuid import uuid4

from app.domain import EventCreate, EventRead, RSVPCreate, RSVPRead


class InMemoryEventRepository:
    def __init__(self) -> None:
        self._events: dict[str, EventRead] = {}
        self._rsvps: dict[str, list[RSVPRead]] = {}

    def list_events(self) -> list[EventRead]:
        return list(self._events.values())

    def create_event(self, payload: EventCreate) -> EventRead:
        event = EventRead(id=str(uuid4()), **payload.model_dump())
        self._events[event.id] = event
        self._rsvps[event.id] = []
        return event

    def get_event_by_slug(self, slug: str) -> EventRead | None:
        return next((event for event in self._events.values() if event.slug == slug), None)

    def add_rsvp(self, event_id: str, payload: RSVPCreate) -> RSVPRead:
        rsvp = RSVPRead(id=str(uuid4()), event_id=event_id, **payload.model_dump())
        self._rsvps.setdefault(event_id, []).append(rsvp)
        return rsvp

    def list_rsvps(self, event_id: str) -> list[RSVPRead]:
        return list(self._rsvps.get(event_id, []))


repo = InMemoryEventRepository()


def seed_events(repo: InMemoryEventRepository) -> None:
    from app.domain import EventCreate, EventModule, EventType, ModuleKey

    if repo.list_events():
        return

    repo.create_event(
        EventCreate(
            title="Marina & Lucas",
            slug="marina-e-lucas",
            event_type=EventType.wedding,
            description="Cerimônia ao entardecer com recepção no mesmo local.",
            starts_at="2026-09-12T17:30:00-03:00",
            location_name="Jardim das Acácias",
            location_address="Estrada das Flores, 1250",
            modules=[
                EventModule(key=ModuleKey.rsvp, enabled=True),
                EventModule(key=ModuleKey.gifts, enabled=False),
                EventModule(key=ModuleKey.messages, enabled=False),
                EventModule(key=ModuleKey.gallery, enabled=False),
                EventModule(key=ModuleKey.countdown, enabled=True),
                EventModule(key=ModuleKey.map, enabled=True),
                EventModule(key=ModuleKey.faq, enabled=True),
            ],
        )
    )
