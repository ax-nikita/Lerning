from fastapi.testclient import TestClient

from agent_quality_gateway.api.app import app, get_llm_client

import pytest

from agent_quality_gateway.core import run_prompt

client = TestClient(app)

@pytest.mark.asyncio
async def test_health_api() -> None:
    response = client.get(
        "/health"
    )
    assert response.status_code == 200
    assert response.json() == {"status": "ok"}

@pytest.mark.asyncio
async def test_fake_generate() -> None:
    prompt = "Hello"

    response = client.post(
        "/v1/run",
        json={
            "prompt": prompt,
        },
    )
    assert response.status_code == 200

    llm_client = get_llm_client()
    llm_result = run_prompt(llm_client, prompt)

    assert response.json()["content"] == llm_result

@pytest.mark.asyncio
async def test_fake_generate() -> None:
    response = client.post(
        "/v1/run",
        json={
            "prompt": "",
        },
    )

    assert response.status_code == 422