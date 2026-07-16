from fastapi import APIRouter, HTTPException

from app.domain import EventCreate, EventRead, RSVPCreate, RSVPRead
from app.repositories import InMemoryEventRepository

router = APIRouter(prefix="/events", tags=["events"])
repo = InMemoryEventRepository()


@router.get("", response_model=list[EventRead])
def list_events() -> list[EventRead]:
    return repo.list_events()


@router.post("", response_model=EventRead, status_code=201)
def create_event(payload: EventCreate) -> EventRead:
    return repo.create_event(payload)


@router.get("/{slug}", response_model=EventRead)
def get_event(slug: str) -> EventRead:
    event = repo.get_event_by_slug(slug)
    if not event:
        raise HTTPException(status_code=404, detail="Evento não encontrado")
    return event


@router.get("/{slug}/rsvps", response_model=list[RSVPRead])
def list_event_rsvps(slug: str) -> list[RSVPRead]:
    event = repo.get_event_by_slug(slug)
    if not event:
        raise HTTPException(status_code=404, detail="Evento não encontrado")
    return repo.list_rsvps(event.id)


@router.post("/{slug}/rsvps", response_model=RSVPRead, status_code=201)
def create_event_rsvp(slug: str, payload: RSVPCreate) -> RSVPRead:
    event = repo.get_event_by_slug(slug)
    if not event:
        raise HTTPException(status_code=404, detail="Evento não encontrado")
    return repo.add_rsvp(event.id, payload)

