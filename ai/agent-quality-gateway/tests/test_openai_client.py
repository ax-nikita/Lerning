import pytest
import httpx

from agent_quality_gateway.exceptions import TransportError
from agent_quality_gateway.openai_client import OpenAICompatibleClient


@pytest.mark.asyncio
async def test_generate_returns_content() -> None:
    def handler(request: httpx.Request) -> httpx.Response:
        return httpx.Response(
            status_code=200,
            json={
                "choices": [
                    {
                        "message": {
                            "content": "fake llm response"
                        }
                    }
                ]
            },
        )

    transport = httpx.MockTransport(handler)

    timeout = httpx.Timeout(
        connect=3.0,
        read=20.0,
        write=5.0,
        pool=3.0,
    )

    openai_client = OpenAICompatibleClient(
        api_url="http://localhost:8080",
        api_key="dwadawda",
        transport=transport,
        timeout=timeout,
        model="qwen"
    )

    content = await openai_client.generate("Hello")

    assert content == 'fake llm response'

@pytest.mark.asyncio
async def test_generate_maps_http_500_to_transport_error() -> None:
    def handler(request: httpx.Request) -> httpx.Response:
        return httpx.Response(
            status_code=500,
            json={
                "error": "model unavailable"
            },
        )

    transport = httpx.MockTransport(handler)

    timeout = httpx.Timeout(
        connect=3.0,
        read=20.0,
        write=5.0,
        pool=3.0,
    )

    openai_client = OpenAICompatibleClient(
        api_url="http://localhost:8080",
        api_key="dwadawda",
        transport=transport,
        timeout=timeout,
        model="qwen"
    )

    with pytest.raises(TransportError) as error:
        await openai_client.generate("Hello")

    message = str(error.value)

    assert "500" in message
    assert "model unavailable" in message