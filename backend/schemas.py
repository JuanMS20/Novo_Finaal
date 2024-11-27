from pydantic import BaseModel, EmailStr, constr, confloat
from datetime import datetime
from typing import Optional, List

# User schemas
class UserBase(BaseModel):
    email: EmailStr
    name: Optional[str] = None

class UserCreate(UserBase):
    password: constr(min_length=6)

class User(UserBase):
    id: int
    is_active: bool
    created_at: datetime

    class Config:
        from_attributes = True

# Product schemas
class ProductBase(BaseModel):
    title: str
    description: Optional[str] = None
    price: confloat(gt=0)
    image_url: Optional[str] = None
    condition_state: str
    category: str

class ProductCreate(ProductBase):
    pass

class ProductUpdate(ProductBase):
    title: Optional[str] = None
    price: Optional[confloat(gt=0)] = None
    condition_state: Optional[str] = None
    category: Optional[str] = None

class Product(ProductBase):
    id: int
    seller_id: int
    created_at: datetime
    is_active: bool

    class Config:
        from_attributes = True

class ProductWithSeller(Product):
    seller: User

    class Config:
        from_attributes = True

# Token schemas
class Token(BaseModel):
    access_token: str
    token_type: str

class TokenData(BaseModel):
    email: Optional[str] = None 