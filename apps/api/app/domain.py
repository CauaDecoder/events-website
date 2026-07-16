from enum import Enum
from typing import Any

from pydantic import BaseModel, Field


class EventType(str, Enum):
    wedding = "wedding"
    birthday = "birthday"
    baby_shower = "baby_shower"
    debutante = "debutante"
    graduation = "graduation"
    corporate = "corporate"


class ModuleKey(str, Enum):
    rsvp = "rsvp"
    gifts = "gifts"
    messages = "messages"
    gallery = "gallery"
    countdown = "countdown"
    map = "map"
    faq = "faq"
    payments = "payments"


class EventModule(BaseModel):
    key: ModuleKey
    enabled: bool = True
    config: dict[str, Any] = Field(default_factory=dict)


class EventBase(BaseModel):
    title: str
    slug: str
    event_type: EventType
    description: str
    starts_at: str
    location_name: str
    location_address: str
    modules: list[EventModule] = Field(default_factory=list)


class EventCreate(EventBase):
    pass


class EventRead(EventBase):
    id: str


class RSVPCreate(BaseModel):
    guest_name: str
    email: str | None = None
    phone: str | None = None
    companions: int = 0
    dietary_restrictions: str | None = None


class RSVPRead(RSVPCreate):
    id: str
    event_id: str
    status: str = "confirmed"

