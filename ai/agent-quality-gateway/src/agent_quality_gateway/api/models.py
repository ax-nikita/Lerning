from pydantic import BaseModel, Field


class AppRequest(BaseModel):
    prompt: str

class AppResponse(BaseModel):
    content: str = Field (min_length=1)