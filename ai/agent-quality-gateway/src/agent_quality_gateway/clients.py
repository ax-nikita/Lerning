from typing import Protocol

class LLMClient(Protocol):
    def generate(self, prompt: str) -> str:
        ...

class FakeLLMClient:
    def generate(self, prompt: str) -> str:
        return "fake response"