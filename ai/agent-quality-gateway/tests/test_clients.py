from agent_quality_gateway.clients import FakeLLMClient
from agent_quality_gateway.core import run_prompt


def test_fake_generate() -> None:
    result = run_prompt(FakeLLMClient(), "бла блф бла")
    assert result == "fake response"