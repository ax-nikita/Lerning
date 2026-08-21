from agent_quality_gateway.exceptions import SchemaError


def validate_temperature(temperature: float) -> bool:
    return 0.0 <= temperature <= 1.0

def require_field(raw_request: dict, field: str):
    if field not in raw_request:
        raise SchemaError(f"Field {field} is required")
    return raw_request[field]