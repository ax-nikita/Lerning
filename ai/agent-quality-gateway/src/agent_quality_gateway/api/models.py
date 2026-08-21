from pydantic import BaseModel, Field


class AppRequest(BaseModel):
    prompt: str = Field(min_length=1)

class AppResponse(BaseModel):
    content: str