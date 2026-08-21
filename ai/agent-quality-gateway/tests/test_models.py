import json


import pytest


from agent_quality_gateway.models import EvaluationResult, EvaluationStatus


def test_valid_status_is_accepted() -> None:
    result = EvaluationResult(
            source_index=0,
            request_id="req-001",
            status=EvaluationStatus.PASS,
            message="ok",
        )
    assert result.status == EvaluationStatus.PASS


def test_invalid_status_is_rejected() -> None:
    with pytest.raises(TypeError):
        EvaluationResult(
            source_index=0,
            request_id="req-001",
            status="pass",
            message="ok",
        )


def test_status_is_serialized_as_string() -> None:
    result = EvaluationResult(
            source_index=0,
            request_id="req-001",
            status=EvaluationStatus.PASS,
            message="ok",
        )

    data = json.loads(result.to_json())

    assert data["status"] == EvaluationStatus.PASS.value


def test_json_serialization_is_deterministic() -> None:
    result = EvaluationResult(
            source_index=0,
            request_id="req-001",
            status=EvaluationStatus.PASS,
            message="ok",
        )

    first = result.to_json()
    second = result.to_json()

    assert first == second


def test_none_request_id_is_serialized_as_null() -> None:
    result = EvaluationResult(
            source_index=0,
            request_id=None,
            status=EvaluationStatus.PASS,
            message="ok",
        )

    data = json.loads(result.to_json())

    assert data["request_id"] is None