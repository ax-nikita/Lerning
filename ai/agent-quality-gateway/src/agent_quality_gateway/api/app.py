from fastapi import FastAPI
import httpx

from agent_quality_gateway.api.models import AppResponse, AppRequest

app = FastAPI(
    title="Agent Quality Gateway",
    version="0.1.0",
)


@app.get("/health")
async def health() -> dict:
    return {"status": "ok"}


@app.post("/v1/run", response_model=AppResponse)
async def run(request: AppRequest) -> AppResponse:
    content = AppResponse(
        content=request.prompt
    )
    return content