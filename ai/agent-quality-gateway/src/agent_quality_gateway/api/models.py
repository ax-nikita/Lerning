from pydantic import BaseModel


class AppRequest(BaseModel):
    prompt: str

class AppResponse(BaseModel):
    response: str