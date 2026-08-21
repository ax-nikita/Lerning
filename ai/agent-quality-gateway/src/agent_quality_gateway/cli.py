from agent_quality_gateway.exceptions import SchemaError
from agent_quality_gateway.models import Request, EvaluationStatus, EvaluationResult
from agent_quality_gateway.validation import validate_temperature, require_field

def main() -> None:

    raw_requests: list[dict] = [
        {
            "id": "req-001",
            "model": "qwen3.5-9b",
            "temperature": 0.2,
            "output_format": "json",
        },
        {
            "id": "req-002",
            "model": "qwen3.5-4b",
            "temperature": 1.5,
            "output_format": "text",
        },
        {
            "id": "req-003",
            "temperature": 0.7,
        },
        {
            "id": "req-004",
            "model": "qwen3.5-9b",
            "output_format": "json",
        }
    ]

    requests: list[Request] = []
    errors: list[str] = []
    results: list[EvaluationResult] = []

    for index, raw_request in enumerate(raw_requests):
        request_id: str | None = raw_request.get("id")
        status = EvaluationStatus.BLOCKED
        message = f"Request {index} blocked"

        try:
            temperature = require_field(raw_request, "temperature")

            if validate_temperature(temperature):
                requests.append(Request(
                    id=require_field(raw_request, "id"),
                    model=require_field(raw_request,"model"),
                    output_format=require_field(raw_request,"output_format"),
                    temperature=temperature
                ))
                status = EvaluationStatus.PASS
                message = f"Request {index} valid"
            else:
                errors.append(f"Request {index} not valid temperature")
                status = EvaluationStatus.FAIL
                message = f"Request {index} failed"
        except SchemaError as error:
            status = EvaluationStatus.BLOCKED
            message = f"Request {index} blocked"
            errors.append(f"Request {index} missing {error}")

        result = EvaluationResult(
            source_index=index,
            request_id=request_id,
            status=status,
            message=message
        )

        results.append(result)


    for request in requests:
        print(f"{request.id} format: {request.output_format}")

if __name__ == "__main__":
    main()