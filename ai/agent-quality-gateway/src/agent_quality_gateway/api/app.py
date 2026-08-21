import fastapi
from fastapi import FastAPI, Depends
from starlette import status
from starlette.responses import JSONResponse

from agent_quality_gateway.api.models import AppResponse, AppRequest, ErrorResponse
from agent_quality_gateway.clients import LLMClient, FakeLLMClient
from agent_quality_gateway.core import run_prompt
from agent_quality_gateway.exceptions import TransportError

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

    try:
        content = await run_prompt(client, request.prompt)
    except TransportError as error:
        JSONResponse(
            status_code=status.HTTP_502_BAD_GATEWAY,
            content=ErrorResponse(
                error="transport_error",
                message=str(error)
            ).model_dump()
        )


    return AppResponse(
        content=content
    )