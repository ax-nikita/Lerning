from fastapi import FastAPI
from fastapi.testclient import TestClient

from agent_quality_gateway.api.app import app, get_llm_client

import pytest

from agent_quality_gateway.clients import TransportErrorLLMClient, FakeLLMClient
from agent_quality_gateway.core import run_prompt

client = TestClient(app)

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

    assert response.status_code == 502