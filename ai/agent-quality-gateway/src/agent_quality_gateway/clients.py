from typing import Protocol

from agent_quality_gateway.exceptions import TransportError


class LLMClient(Protocol):
    async def generate(self, prompt: str) -> str:
        ...

class FakeLLMClient:
    async def generate(self, prompt: str) -> str:
        return "fake response"

class TransportErrorLLMClient:
    async def generate(self, prompt: str) -> str:
        raise TransportError("fake TransportError")