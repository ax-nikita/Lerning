from dataclasses import dataclass

def validate_temperature(temperature: float) -> bool:
    return 0.0 <= temperature <= 1.0

@dataclass
class Request:
    id: str
    model: str
    temperature: float

raw_requests: list[dict] = [
    {
        "id": "req-001",
        "model": "qwen3.5-9b",
        "temperature": 0.2,
    },
    {
        "id": "req-002",
        "model": "qwen3.5-4b",
        "temperature": 1.5,
    },
    {
        "id": "req-003",
        "temperature": 0.7,
    },
]

requests: list[Request] = []
errors: list[str] = []

for index, raw_request in enumerate(raw_requests):
    try:
        if validate_temperature(raw_request["temperature"]):
            requests.append(Request(raw_request["id"], raw_request["model"], raw_request["temperature"]))
        else:
            errors.append(f"Request {index} not valid temperature")
    except KeyError as error:
        errors.append(f"Request {index} missing {error} field")

print(requests)
print(errors)