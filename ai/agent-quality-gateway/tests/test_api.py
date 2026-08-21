from fastapi import FastAPI
from fastapi.testclient import TestClient

from agent_quality_gateway.api.app import app, get_llm_client

import pytest

from agent_quality_gateway.clients import FakeLLMClient
from agent_quality_gateway.core import run_prompt
from agent_quality_gateway.exceptions import TransportError

client = TestClient(app)

class TransportErrorLLMClient:
    async def generate(self, prompt: str) -> str:
        raise TransportError("fake TransportError")

def get_transport_error_llm_client() -> TransportErrorLLMClient:
    return TransportErrorLLMClient()

def get_fake_llm_client() -> FakeLLMClient:
    return FakeLLMClient()

@pytest.mark.asyncio
async def test_health_api() -> None:
    app.dependency_overrides[get_llm_client] = get_fake_llm_client

    response = client.get(
        "/health"
    )

    app.dependency_overrides.clear()

    assert response.status_code == 200
    assert response.json() == {"status": "ok"}

@pytest.mark.asyncio
async def test_fake_generate() -> None:
    prompt = "Hello"

    app.dependency_overrides[get_llm_client] = get_fake_llm_client

    response = client.post(
        "/v1/run",
        json={
            "prompt": prompt,
        },
    )

    app.dependency_overrides.clear()

    assert response.status_code == 200

    llm_client = get_llm_client()
    llm_result = await run_prompt(llm_client, prompt)

    assert response.json()["content"] == llm_result

@pytest.mark.asyncio
async def test_empty_prompt() -> None:
    app.dependency_overrides[get_llm_client] = get_fake_llm_client

    response = client.post(
        "/v1/run",
        json={
            "prompt": "",
        },
    )

    app.dependency_overrides.clear()

    assert response.status_code == 422


@pytest.mark.asyncio
async def test_transport_errpr_expection() -> None:
    app.dependency_overrides[get_llm_client] = get_transport_error_llm_client

    response = client.post(
        "/v1/run",
        json={
            "prompt": "daw",
        },
    )

    app.dependency_overrides.clear()

    error_content = ""

    try:
        result = await get_transport_error_llm_client().generate("test")
    except TransportError as error:
        error_content = str(error)

    assert response.status_code == 502
    assert response.json()["error"] == "transport_error"
    assert response.json()["message"] == error_content
