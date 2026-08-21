from typing import Protocol


class LLMClient(Protocol):
    async def generate(self, prompt: str) -> str:
        ...

class FakeLLMClient:
    async def generate(self, prompt: str) -> str:
        return "fake response"