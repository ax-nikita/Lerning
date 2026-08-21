from dataclasses import dataclass

def validate_temperature(temperature: float) -> bool:
    return 0.0 <= temperature <= 1.0

@dataclass
class Request:
    id: str
    model: str
    temperature: float

requests: list[Request] = [
    Request("req-001", "qwen3.5-9b", 0.2),
    Request("req-002", "qwen3.5-4b", 1.5),
    Request("req-003", "qwen3.5-9b", 0.7),
]

for request in requests:
    is_valid = validate_temperature(request.temperature)
    print(f"{request.id}: {is_valid}")