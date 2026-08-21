import httpx
import asyncio
import json
from agent_quality_gateway.exceptions import TransportError

def handler(request: httpx.Request) -> httpx.Response:

    return httpx.Response(
        status_code=200,
        json={
            "error": "model unavailable"
        },
    )

async def slow_task() -> None:
    print("start")
    await asyncio.sleep(10)
    print("end")


async def main() -> None:
    transport = httpx.MockTransport(handler)

    timeout = httpx.Timeout(
        connect=5.0,
        read=30.0,
        write=10.0,
        pool=5.0,
    )

    async with httpx.AsyncClient(transport=transport, timeout=timeout) as client:
        response = await client.post(
            "https://llm.test/v1/chat/completions",
            json={
                "model": "qwen",
                "prompt": "Hello",
            },
        )

        try:
            response.raise_for_status()

            data = response.json()

            for key, value in data.items():
                print(value)
        except httpx.HTTPStatusError as error:
            raise TransportError(
                f"HTTP {error.response.status_code}: {error.response.text}"
            ) from error

    task = asyncio.create_task(slow_task())
    await asyncio.sleep(1)
    task.cancel()

    try:
        await task
    except asyncio.CancelledError:
        print("cancelled")


asyncio.run(main())