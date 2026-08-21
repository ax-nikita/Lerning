from agent_quality_gateway.clients import FakeLLMClient
from agent_quality_gateway.core import run_prompt
import pytest

@pytest.mark.asyncio
async def test_fake_generate() -> None:
    result = await run_prompt(FakeLLMClient(), "бла блф бла")
    assert result == "fake response"