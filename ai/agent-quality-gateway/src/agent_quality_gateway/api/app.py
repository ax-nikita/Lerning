import fastapi
from fastapi import FastAPI, Depends
from starlette import status
from starlette.responses import JSONResponse

from agent_quality_gateway.api.models import AppResponse, AppRequest, ErrorResponse
from agent_quality_gateway.clients import LLMClient, FakeLLMClient
from agent_quality_gateway.core import run_prompt
from agent_quality_gateway.exceptions import TransportError
from agent_quality_gateway.models import Request

app = FastAPI(
    title="Agent Quality Gateway",
    version="0.1.0",
)


def get_llm_client() -> LLMClient:
    return FakeLLMClient()


@app.get("/health")
async def health() -> dict:
    return {"status": "ok"}


@app.exception_handler(TransportError)
async def transport_error_handler(request: Request, exc: TransportError) -> JSONResponse:
    return JSONResponse(
        status_code=status.HTTP_502_BAD_GATEWAY,
        content=ErrorResponse(
            error="transport_error",
            message=str(exc)
        ).model_dump()
    )

@app.post("/v1/run", response_model=AppResponse)
async def run(
        request: AppRequest,
        client: LLMClient = Depends(get_llm_client),

    ) -> AppResponse:

    content = await run_prompt(client, request.prompt)

    return AppResponse(
        content=content
    )