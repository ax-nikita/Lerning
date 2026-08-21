from agent_quality_gateway.clients import LLMClient


def run_prompt(client: LLMClient, prompt: str) -> str:
    return client.generate(prompt)

