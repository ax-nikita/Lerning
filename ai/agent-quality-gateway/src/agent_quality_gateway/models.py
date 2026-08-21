import json
from dataclasses import dataclass
from enum import Enum
from typing import Literal

from agent_quality_gateway.exceptions import SchemaError


@dataclass
class Request:
    id: str
    output_format: OutputFormat
    model: str
    temperature: float


OutputFormat = Literal["text", "json"]

@dataclass
class EvaluationResult:
    source_index: int
    request_id: str | None
    status: EvaluationStatus
    message: str
    def __post_init__(self) -> None:
        if not isinstance(self.status, EvaluationStatus):
            raise TypeError("status must be EvaluationStatus")
    def to_json(self) -> str:
        return json.dumps({
            "source_index": self.source_index,
            "request_id": self.request_id,
            "status": self.status.value,
            "message": self.message
        }, sort_keys=True)



class EvaluationStatus(Enum):
    PASS = "pass"
    FAIL = "fail"
    BLOCKED = "blocked"


@dataclass
class ModelConfig:
    model: str
    temperature: float
    max_tokens: int
    timeout: float