from fastapi import FastAPI, Depends

from agent_quality_gateway.api.models import AppResponse, AppRequest
from agent_quality_gateway.clients import LLMClient, FakeLLMClient
from agent_quality_gateway.core import run_prompt

app = FastAPI(
    title="Agent Quality Gateway",
    version="0.1.0",
)


def get_llm_client() -> LLMClient:
    return FakeLLMClient()


@app.get("/health")
async def health() -> dict:
    return {"status": "ok"}


@app.post("/v1/run", response_model=AppResponse)
async def run(
        request: AppRequest,
        client: LLMClient = Depends(get_llm_client),

    ) -> AppResponse:
    content = await run_prompt(client, request.prompt)

    return AppResponse(
        content=content
    )