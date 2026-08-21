from agent_quality_gateway.clients import LLMClient


async def run_prompt(client: LLMClient, prompt: str) -> str:
    return await client.generate(prompt)

